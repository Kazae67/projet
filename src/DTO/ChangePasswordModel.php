<?php

namespace App\DTO;

class ChangePasswordModel
{
    // Propriété pour stocker l'ancien mot de passe
    private $oldPassword;

    // Propriété pour stocker le nouveau mot de passe
    private $newPassword;

    // Méthode pour obtenir l'ancien mot de passe
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    // Méthode pour définir l'ancien mot de passe
    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    // Méthode pour obtenir le nouveau mot de passe
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    // Méthode pour définir le nouveau mot de passe
    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }
}
