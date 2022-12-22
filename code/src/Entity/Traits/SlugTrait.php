<?php

namespace App\Entity\Traits;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

// This trait is used to generate a slug from a string
trait SlugTrait
{
    #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        // Use cocur/slugify to generate a slug from the title
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($slug);
        return $this;
    }
}