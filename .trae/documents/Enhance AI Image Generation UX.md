## Overview
The goal is to improve the UX for AI-generated images in the property management interface. Currently, generated images are automatically assigned to either the "Main Image" or "Gallery" based on which button was clicked. The proposed change will allow users to generate images and then selectively assign them to either the Main Image or the Gallery, providing better control and flexibility.

## Proposed Changes

### 1. Unified Generated Image Preview (Frontend)
- **Refactor Preview UI**: Instead of automatic assignment, all generated images (single or gallery) will be displayed in a flexible preview area.
- **Interactive Image Cards**: Each generated image will be presented as a card with:
    - **"Set as Main" Button**: Clicking this will set the image as the property's primary photo.
    - **"Add to Gallery" Button**: Clicking this will add the image to the carousel/gallery collection.
    - **"Remove" Button**: Discards the generated image from the current session.
- **Visual Feedback**: Active selections will be highlighted with badges (e.g., "Main", "In Gallery") and border styles to clearly show their status.

### 2. State Management (JavaScript)
- **Dynamic Hidden Inputs**: The JavaScript will manage hidden inputs (`generated_image_url` and `generated_gallery_images[]`) based on user interactions with the preview cards.
- **Conflict Resolution**: Ensuring only one image is set as "Main" at a time, while multiple images can be added to the gallery.

### 3. Consistency Across Views
- Apply these changes to both `admin/create.blade.php` and `admin/edit.blade.php` to ensure a consistent experience when adding new properties or updating existing ones.

## Technical Steps

### **View Updates**
- Modify the HTML structure in [edit.blade.php](file:///d:/web-proj-php/real_estate_laravel/resources/views/admin/edit.blade.php) and [create.blade.php](file:///d:/web-proj-php/real_estate_laravel/resources/views/admin/create.blade.php) to support the new preview cards.
- Add necessary CSS for the card interactions and badges.

### **Script Enhancements**
- Update the `DOMContentLoaded` listeners to handle the new "Set as Main" and "Add to Gallery" logic.
- Create a helper function `createGeneratedImageCard(imgData)` to generate the interactive preview cards consistently.

### **Verification**
- Test generating a single image and assigning it to the gallery.
- Test generating a gallery and picking one image as the main photo.
- Verify that form submission correctly saves the selected images to the database.
