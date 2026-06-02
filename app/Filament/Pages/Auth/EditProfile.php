<?php

// The namespace is updated to reflect the new location inside the "Admin" panel directory.
namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    /**
     * Override the layout to ensure the main panel layout is used.
     *
     * We change the visibility to public to match the parent class method.
     *
     * @return string
     */
    public function getLayout(): string
    {
        // This forces the use of the default layout with sidebar and header.
        // You can remove the dd() line now.
        return 'filament-panels::components.layout.index';
    }
}
