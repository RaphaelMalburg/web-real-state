<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $properties = Property::latest()->get();
        $bookings = Booking::with('property')->latest()->get();
        $inquiries = Inquiry::latest()->get();

        // Calculate Stats
        $stats = [
            'total_properties' => $properties->count(),
            'total_inquiries' => $inquiries->count(),
            'total_bookings' => $bookings->count(),
            'market_value' => $properties->where('type', 'Sale')->sum('price'),
        ];

        return view('admin.index', compact('properties', 'bookings', 'inquiries', 'stats'));
    }

    public function create()
    {
        return view('admin.create');
    }

    private function processImage($file)
    {
        $extension = $file->getClientOriginalExtension();
        $extension = $extension ? strtolower($extension) : 'jpg';
        $filename = uniqid('property_', true).'.'.$extension;
        $path = $file->storeAs('uploads/properties', $filename, 'public');
        if (!$path) {
            return null;
        }

        return 'storage/uploads/properties/'.$filename;
    }

    private function deleteStoredImage($path)
    {
        if (!$path || !Str::startsWith($path, 'storage/')) {
            return;
        }

        $storagePath = Str::replaceFirst('storage/', '', $path);
        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('AdminController@store: Starting property creation', $request->except(['image_url', 'gallery_images']));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|in:Rent,Sale',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'generated_image_url' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        $generatedImage = $request->input('generated_image_url');
        if ($generatedImage && !$this->isStoredImagePathValid($generatedImage)) {
            \Illuminate\Support\Facades\Log::warning('AdminController@store: Invalid generated image path', ['path' => $generatedImage]);
            return back()->withErrors(['image_url' => 'Generated image is invalid.'])->withInput();
        }

        if ($request->hasFile('image_url')) {
            \Illuminate\Support\Facades\Log::info('AdminController@store: Processing uploaded main image');
            $processed = $this->processImage($request->file('image_url'));
            if (!$processed) {
                \Illuminate\Support\Facades\Log::error('AdminController@store: Failed to process main image');
                return back()->withErrors(['image_url' => 'Unable to process the main image.'])->withInput();
            }
            $validated['image_url'] = $processed;
        } elseif ($generatedImage) {
            \Illuminate\Support\Facades\Log::info('AdminController@store: Using generated main image', ['path' => $generatedImage]);
            $validated['image_url'] = $generatedImage;
        }

        // Handle Gallery Images
        $galleryData = [];
        if ($request->has('generated_gallery_images')) {
            foreach ($request->input('generated_gallery_images') as $genPath) {
                if ($this->isStoredImagePathValid($genPath)) {
                    $galleryData[] = $genPath;
                } else {
                    \Illuminate\Support\Facades\Log::warning('AdminController@store: Invalid generated gallery image', ['path' => $genPath]);
                }
            }
        }

        if ($request->hasFile('gallery_images')) {
            \Illuminate\Support\Facades\Log::info('AdminController@store: Processing uploaded gallery images', ['count' => count($request->file('gallery_images'))]);
            foreach ($request->file('gallery_images') as $file) {
                $processed = $this->processImage($file);
                if (!$processed) {
                    \Illuminate\Support\Facades\Log::error('AdminController@store: Failed to process a gallery image');
                    return back()->withErrors(['gallery_images' => 'One or more gallery images could not be processed.'])->withInput();
                }
                $galleryData[] = $processed;
            }
        }

        // Store as JSON
        $validated['gallery_images'] = !empty($galleryData) ? json_encode($galleryData) : null;

        try {
            $property = Property::create($validated);
            \Illuminate\Support\Facades\Log::info('AdminController@store: Property created successfully', ['id' => $property->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AdminController@store: Failed to create property record', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Database error: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.index')->with('success', 'Property created successfully.');
    }

    public function edit(Property $property)
    {
        return view('admin.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        \Illuminate\Support\Facades\Log::info('AdminController@update: Starting property update', ['id' => $property->id, 'data' => $request->except(['image_url', 'gallery_images'])]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'type' => 'required|in:Rent,Sale',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'generated_image_url' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        $removeRequested = $request->boolean('remove_image_url');
        $generatedImage = $removeRequested ? null : $request->input('generated_image_url');
        $hasGeneratedImage = $generatedImage && $this->isStoredImagePathValid($generatedImage);
        if ($generatedImage && !$hasGeneratedImage) {
            \Illuminate\Support\Facades\Log::warning('AdminController@update: Invalid generated image path', ['path' => $generatedImage]);
            return back()->withErrors(['image_url' => 'Generated image is invalid.'])->withInput();
        }
        if ($removeRequested) {
            \Illuminate\Support\Facades\Log::info('AdminController@update: Removing main image by request');
            $this->deleteStoredImage($property->image_url);
            $validated['image_url'] = null;
        } else {
            if ($request->hasFile('image_url')) {
                \Illuminate\Support\Facades\Log::info('AdminController@update: Replacing main image with upload');
                $this->deleteStoredImage($property->image_url);
                $processed = $this->processImage($request->file('image_url'));
                if (!$processed) {
                    \Illuminate\Support\Facades\Log::error('AdminController@update: Failed to process new main image');
                    return back()->withErrors(['image_url' => 'Unable to process the main image.'])->withInput();
                }
                $validated['image_url'] = $processed;
            } elseif ($hasGeneratedImage) {
                \Illuminate\Support\Facades\Log::info('AdminController@update: Replacing main image with generated one', ['path' => $generatedImage]);
                $this->deleteStoredImage($property->image_url);
                $validated['image_url'] = $generatedImage;
            } else {
                // If no new image and not removing, keep the old one
                unset($validated['image_url']);
            }
        }

        // Handle Gallery Images
        $currentGallery = [];
        if ($property->gallery_images) {
            $decoded = json_decode($property->gallery_images, true);
            if (is_array($decoded)) {
                $currentGallery = $decoded;
            } else {
                // Handle legacy comma-separated
                $currentGallery = array_filter(explode(',', $property->gallery_images));
            }
        }

        $removeGallery = $request->input('remove_gallery_images', []);
        if (!empty($removeGallery)) {
            $removeGallery = array_values(array_filter($removeGallery));
            \Illuminate\Support\Facades\Log::info('AdminController@update: Removing gallery images', ['count' => count($removeGallery)]);
            foreach ($removeGallery as $removePath) {
                $this->deleteStoredImage($removePath);
            }
            $currentGallery = array_values(array_diff($currentGallery, $removeGallery));
        }

        if ($request->has('generated_gallery_images')) {
            foreach ($request->input('generated_gallery_images') as $genPath) {
                if ($this->isStoredImagePathValid($genPath)) {
                    $currentGallery[] = $genPath;
                } else {
                    \Illuminate\Support\Facades\Log::warning('AdminController@update: Invalid generated gallery image', ['path' => $genPath]);
                }
            }
        }

        if ($request->hasFile('gallery_images')) {
            \Illuminate\Support\Facades\Log::info('AdminController@update: Adding uploaded gallery images', ['count' => count($request->file('gallery_images'))]);
            foreach ($request->file('gallery_images') as $file) {
                $processed = $this->processImage($file);
                if (!$processed) {
                    \Illuminate\Support\Facades\Log::error('AdminController@update: Failed to process a gallery image');
                    return back()->withErrors(['gallery_images' => 'One or more gallery images could not be processed.'])->withInput();
                }
                $currentGallery[] = $processed;
            }
            // Update the validated array with the merged gallery
            $validated['gallery_images'] = json_encode($currentGallery);
        } elseif (!empty($removeGallery) || $request->has('generated_gallery_images')) {
            $validated['gallery_images'] = json_encode($currentGallery);
        } else {
            unset($validated['gallery_images']);
        }

        try {
            $property->update($validated);
            \Illuminate\Support\Facades\Log::info('AdminController@update: Property updated successfully', ['id' => $property->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AdminController@update: Failed to update property record', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Database error: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('admin.index')->with('success', 'Property updated successfully.');
    }

    public function confirmDelete(Property $property)
    {
        return view('admin.delete', compact('property'));
    }

    public function destroy(Property $property)
    {
        \Illuminate\Support\Facades\Log::info('AdminController@destroy: Deleting property', ['id' => $property->id]);
        try {
            $property->delete();
            \Illuminate\Support\Facades\Log::info('AdminController@destroy: Property deleted successfully', ['id' => $property->id]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AdminController@destroy: Failed to delete property', ['id' => $property->id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to delete property.']);
        }
        return redirect()->route('admin.index')->with('success', 'Property removed successfully.');
    }

    public function aiGenerateImage(Request $request)
    {
        // Increase execution time to 2 minutes for single image generation
        set_time_limit(120);

        \Illuminate\Support\Facades\Log::info('AdminController@aiGenerateImage: Starting single image generation', $request->except(['password', 'password_confirmation']));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Rent,Sale',
            'price' => 'nullable|numeric',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
            'style' => 'nullable|string|max:200',
        ]);

        try {
            $prompt = $this->buildImagePrompt($validated);
            \Illuminate\Support\Facades\Log::info('AdminController@aiGenerateImage: Prompt built', ['prompt' => $prompt]);

            $imageResult = $this->openRouterImageRequest($prompt);

            if (!$imageResult) {
                \Illuminate\Support\Facades\Log::error('AdminController@aiGenerateImage: OpenRouter returned no result');
                return response()->json(['message' => 'Image generation service returned no result.'], 422);
            }

            $path = $this->storeGeneratedImage($imageResult['bytes'], $imageResult['extension']);
            if (!$path) {
                \Illuminate\Support\Facades\Log::error('AdminController@aiGenerateImage: Failed to store generated image');
                return response()->json(['message' => 'Unable to store generated image.'], 422);
            }

            \Illuminate\Support\Facades\Log::info('AdminController@aiGenerateImage: Image generated and stored successfully', ['path' => $path]);

            return response()->json([
                'path' => $path,
                'url' => asset($path),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Image Generation Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error during image generation: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateGallery(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('AdminController@aiGenerateGallery: Starting gallery generation', $request->except(['password', 'password_confirmation']));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Rent,Sale',
            'quantity' => 'required|integer|min:1|max:4',
            'price' => 'nullable|numeric',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
            'style' => 'nullable|string|max:200',
        ]);

        $quantity = (int) $validated['quantity'];
        $generatedImages = [];
        $errors = [];

        try {
            for ($i = 0; $i < $quantity; $i++) {
                // Add variation to prompt for gallery images
                $promptVariation = match ($i) {
                    0 => 'exterior view, wide angle',
                    1 => 'living room interior, modern design',
                    2 => 'kitchen interior, spacious',
                    3 => 'master bedroom, cozy',
                    default => 'interior view'
                };

                $prompt = $this->buildImagePrompt($validated) . ', ' . $promptVariation;
                \Illuminate\Support\Facades\Log::info("AdminController@aiGenerateGallery: Generating image $i", ['prompt' => $prompt]);

                $imageResult = $this->openRouterImageRequest($prompt);

                if ($imageResult) {
                    $path = $this->storeGeneratedImage($imageResult['bytes'], $imageResult['extension']);
                    if ($path) {
                        $generatedImages[] = [
                            'path' => $path,
                            'url' => asset($path),
                        ];
                    } else {
                        \Illuminate\Support\Facades\Log::error("AdminController@aiGenerateGallery: Failed to store image $i");
                        $errors[] = "Failed to store image $i";
                    }
                } else {
                    \Illuminate\Support\Facades\Log::error("AdminController@aiGenerateGallery: Failed to generate image $i");
                    $errors[] = "Failed to generate image $i";
                }
            }

            if (empty($generatedImages)) {
                 \Illuminate\Support\Facades\Log::error('AI Gallery Generation Failed', ['errors' => $errors]);
                return response()->json(['message' => 'Failed to generate any images.'], 500);
            }

            \Illuminate\Support\Facades\Log::info('AdminController@aiGenerateGallery: Gallery generation completed', ['count' => count($generatedImages), 'errors' => $errors]);

            return response()->json([
                'images' => $generatedImages,
                'partial_errors' => $errors // Inform frontend if some failed
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Gallery Generation Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error during gallery generation: ' . $e->getMessage()], 500);
        }
    }

    public function aiGenerateDescription(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Rent,Sale',
            'price' => 'required|numeric',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'sqft' => 'nullable|integer',
        ]);

        $lines = [
            'Title: '.$validated['title'],
            'Type: '.$validated['type'],
            'Price: '.$validated['price'],
        ];

        if (!empty($validated['address'])) {
            $lines[] = 'Address: '.$validated['address'];
        }
        if (!empty($validated['bedrooms'])) {
            $lines[] = 'Bedrooms: '.$validated['bedrooms'];
        }
        if (!empty($validated['bathrooms'])) {
            $lines[] = 'Bathrooms: '.$validated['bathrooms'];
        }
        if (!empty($validated['sqft'])) {
            $lines[] = 'Sqft: '.$validated['sqft'];
        }

        $messages = [
            [
                'role' => 'system',
                'content' => 'You write concise real estate listing descriptions. Use a professional tone, 2-3 sentences, no emojis.',
            ],
            [
                'role' => 'user',
                'content' => "Create a polished listing description using the details below:\n".implode("\n", $lines),
            ],
        ];

        $description = $this->openRouterRequest($messages, 220, 0.7);
        if (!$description) {
            return response()->json(['message' => 'AI service is unavailable.'], 422);
        }

        return response()->json(['text' => $description]);
    }

    public function aiImproveDescription(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
        ]);

        $messages = [
            [
                'role' => 'system',
                'content' => 'Improve real estate descriptions for clarity and flow. Keep facts, 2-3 sentences, no emojis.',
            ],
            [
                'role' => 'user',
                'content' => "Improve this description:\n".$validated['description'],
            ],
        ];

        $description = $this->openRouterRequest($messages, 220, 0.5);
        if (!$description) {
            return response()->json(['message' => 'AI service is unavailable.'], 422);
        }

        return response()->json(['text' => $description]);
    }

    private function openRouterRequest(array $messages, int $maxTokens = 220, float $temperature = 0.6): ?string
    {
        $apiKey = env('OPENROUTER_API_KEY');
        if (!$apiKey) {
            return null;
        }

        $response = Http::timeout(45)->withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => env('OPENROUTER_MODEL', 'openrouter/auto'),
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $content = data_get($response->json(), 'choices.0.message.content');
        if (!is_string($content)) {
            return null;
        }

        return trim($content);
    }

    private function buildImagePrompt(array $data): string
    {
        $parts = [
            'High-quality real estate photo of a property',
        ];

        if (!empty($data['title'])) {
            $parts[] = 'named '.$data['title'];
        }
        if (!empty($data['type'])) {
            $parts[] = 'listed for '.$data['type'];
        }
        if (!empty($data['address'])) {
            $parts[] = 'located at '.$data['address'];
        }
        if (!empty($data['bedrooms']) || !empty($data['bathrooms']) || !empty($data['sqft'])) {
            $details = [];
            if (!empty($data['bedrooms'])) {
                $details[] = $data['bedrooms'].' bedrooms';
            }
            if (!empty($data['bathrooms'])) {
                $details[] = $data['bathrooms'].' bathrooms';
            }
            if (!empty($data['sqft'])) {
                $details[] = $data['sqft'].' sqft';
            }
            $parts[] = 'showing '.implode(', ', $details);
        }
        if (!empty($data['style'])) {
            $parts[] = 'style notes: '.$data['style'];
        }

        $parts[] = 'bright natural light, wide angle, realistic, no people';

        return implode(', ', $parts);
    }

    private function openRouterImageRequest(string $prompt): ?array
    {
        $apiKey = env('OPENROUTER_API_KEY');
        if (!$apiKey) {
            return null;
        }

        $response = Http::timeout(120)->withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => env('OPENROUTER_IMAGE_MODEL', 'sourceful/riverflow-v2-standard-preview'),
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'modalities' => ['image'],
        ]);

        if (!$response->successful()) {
            \Illuminate\Support\Facades\Log::error('OpenRouter image request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'prompt' => $prompt
            ]);
            return null;
        }

        $json = $response->json();
        \Illuminate\Support\Facades\Log::debug('OpenRouter image response received', ['json' => $json]);

        // Try various possible paths for the image URL based on OpenRouter/OpenAI multimodal response formats
        $imageUrl = data_get($json, 'choices.0.message.images.0.image_url.url')
                 ?? data_get($json, 'choices.0.message.images.0.imageUrl.url')
                 ?? data_get($json, 'choices.0.message.images.0.image_url')
                 ?? data_get($json, 'choices.0.message.images.0.imageUrl')
                 ?? data_get($json, 'choices.0.message.content.0.image_url.url') // Multi-part content
                 ?? data_get($json, 'data.0.url'); // Fallback for standard image generation endpoint if routed there

        if (!is_string($imageUrl) || $imageUrl === '') {
            \Illuminate\Support\Facades\Log::error('OpenRouter image URL could not be resolved from response', ['json' => $json]);
            return null;
        }

        \Illuminate\Support\Facades\Log::info('OpenRouter image URL resolved', ['imageUrl' => substr($imageUrl, 0, 100) . '...']);

        if (str_starts_with($imageUrl, 'data:image/')) {
            $parts = explode(',', $imageUrl, 2);
            if (count($parts) !== 2) {
                return null;
            }
            $header = $parts[0];
            $payload = $parts[1];
            $mime = null;
            if (preg_match('#^data:(image/[^;]+);base64$#', $header, $matches)) {
                $mime = $matches[1];
            }
            $bytes = base64_decode($payload, true);
            if ($bytes === false) {
                return null;
            }
            $extension = $mime && str_contains($mime, 'jpeg') ? 'jpg' : 'png';
            return ['bytes' => $bytes, 'extension' => $extension];
        }

        $download = Http::timeout(60)->get($imageUrl);
        if (!$download->successful()) {
            return null;
        }

        $contentType = $download->header('Content-Type', '');
        $extension = str_contains($contentType, 'jpeg') ? 'jpg' : 'png';

        return ['bytes' => $download->body(), 'extension' => $extension];
    }

    private function storeGeneratedImage(string $bytes, string $extension): ?string
    {
        $extension = strtolower($extension);
        if (!in_array($extension, ['png', 'jpg', 'jpeg'], true)) {
            $extension = 'png';
        }

        $filename = uniqid('ai_property_', true).'.'.$extension;
        $storagePath = 'uploads/properties/'.$filename;
        $saved = Storage::disk('public')->put($storagePath, $bytes);
        if (!$saved) {
            return null;
        }

        return 'storage/'.$storagePath;
    }

    private function isStoredImagePathValid(string $path): bool
    {
        if (!Str::startsWith($path, 'storage/')) {
            return false;
        }
        $storagePath = Str::replaceFirst('storage/', '', $path);
        return Storage::disk('public')->exists($storagePath);
    }
}
