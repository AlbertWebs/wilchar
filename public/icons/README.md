# PWA Icons for Wilchar LMS

This directory should contain the following icon files for Progressive Web App (PWA) support:

## Required Icons

- `icon-72x72.png` - 72x72 pixels
- `icon-96x96.png` - 96x96 pixels
- `icon-128x128.png` - 128x128 pixels
- `icon-144x144.png` - 144x144 pixels
- `icon-152x152.png` - 152x152 pixels (iOS)
- `icon-192x192.png` - 192x192 pixels (Android)
- `icon-384x384.png` - 384x384 pixels
- `icon-512x512.png` - 512x512 pixels (Android)

## Shortcut Icons (Optional)

- `shortcut-dashboard.png` - 96x96 pixels
- `shortcut-applications.png` - 96x96 pixels
- `shortcut-loans.png` - 96x96 pixels
- `shortcut-collections.png` - 96x96 pixels
- `shortcut-reports.png` - 96x96 pixels

## Screenshots (Optional)

- `../screenshots/desktop-1.png` - 1280x720 pixels
- `../screenshots/mobile-1.png` - 750x1334 pixels

## How to Generate Icons

### Option 1: Using Online Tools
1. Visit https://realfavicongenerator.net/ or https://www.pwabuilder.com/imageGenerator
2. Upload your logo/icon (recommended: 512x512px or larger)
3. Generate all required sizes
4. Download and place them in this directory

### Option 2: Using ImageMagick (Command Line)
```bash
# If you have a source icon (e.g., logo.png)
convert logo.png -resize 72x72 icon-72x72.png
convert logo.png -resize 96x96 icon-96x96.png
convert logo.png -resize 128x128 icon-128x128.png
convert logo.png -resize 144x144 icon-144x144.png
convert logo.png -resize 152x152 icon-152x152.png
convert logo.png -resize 192x192 icon-192x192.png
convert logo.png -resize 384x384 icon-384x384.png
convert logo.png -resize 512x512 icon-512x512.png
```

### Option 3: Using Python (Pillow)
```python
from PIL import Image

sizes = [72, 96, 128, 144, 152, 192, 384, 512]
source = Image.open('logo.png')

for size in sizes:
    icon = source.resize((size, size), Image.Resampling.LANCZOS)
    icon.save(f'icon-{size}x{size}.png')
```

## Icon Design Guidelines

- Use a square canvas (1:1 aspect ratio)
- Keep important content within the center 80% to avoid cropping on different devices
- Use transparent background or solid color matching your brand
- Ensure icons are clear and recognizable at small sizes
- For maskable icons, ensure important content is within the safe zone (center 80%)

## Testing

After adding icons, test the PWA installation:
1. Open Chrome DevTools (F12)
2. Go to Application tab
3. Check "Manifest" section
4. Verify all icons are loading correctly
5. Test "Add to Home Screen" on mobile devices

