<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.landing-page');
    }
}
