<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function store(
        UploadedFile $file,
        string $folder = 'mahasiswa',
        int $maxWidth = 600
    ): string {
        $image = $this->manager->read($file->getRealPath());

        $image->scaleDown(width: $maxWidth);

        $path = $folder . '/' . Str::uuid() . '.webp';

        Storage::disk('public')->put(
            $path,
            $image->toWebp(75)->toString()
        );

        return $path;
    }

    /**
     * Store a 600px image and a smaller 150px thumbnail in WebP format.
     *
     * @return array{foto: string, thumbnail: string}
     */
    public function storeWithThumbnail(
        UploadedFile $file,
        string $folder = 'dosen',
        int $maxWidth = 600,
        int $thumbnailWidth = 150
    ): array {
        $fotoPath = $this->store($file, $folder, $maxWidth);

        $image = $this->manager->read($file->getRealPath());
        $image->scaleDown(width: $thumbnailWidth);

        $thumbnailPath = $folder . '/thumbnails/' . Str::uuid() . '.webp';

        Storage::disk('public')->put(
            $thumbnailPath,
            $image->toWebp(75)->toString()
        );

        return [
            'foto' => $fotoPath,
            'thumbnail' => $thumbnailPath,
        ];
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
