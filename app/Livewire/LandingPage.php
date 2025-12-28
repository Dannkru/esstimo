<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;

#[Layout('layouts.app')]
class LandingPage extends Component
{
    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'color' => $category->color,
                ];
            })
            ->toArray();
    }

    public function getColorClassesProperty()
    {
        return [
            'indigo' => [
                'border' => 'hover:border-indigo-500 dark:hover:border-indigo-400',
                'bg' => 'bg-indigo-100 dark:bg-indigo-900/30',
                'gradient' => 'from-indigo-50 to-transparent dark:from-indigo-900/20',
                'text' => 'text-indigo-600 dark:text-indigo-400',
                'hoverText' => 'group-hover:text-indigo-600 dark:group-hover:text-indigo-400',
            ],
            'yellow' => [
                'border' => 'hover:border-yellow-500 dark:hover:border-yellow-400',
                'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                'gradient' => 'from-yellow-50 to-transparent dark:from-yellow-900/20',
                'text' => 'text-yellow-600 dark:text-yellow-400',
                'hoverText' => 'group-hover:text-yellow-600 dark:group-hover:text-yellow-400',
            ],
            'amber' => [
                'border' => 'hover:border-amber-500 dark:hover:border-amber-400',
                'bg' => 'bg-amber-100 dark:bg-amber-900/30',
                'gradient' => 'from-amber-50 to-transparent dark:from-amber-900/20',
                'text' => 'text-amber-600 dark:text-amber-400',
                'hoverText' => 'group-hover:text-amber-600 dark:group-hover:text-amber-400',
            ],
            'blue' => [
                'border' => 'hover:border-blue-500 dark:hover:border-blue-400',
                'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                'gradient' => 'from-blue-50 to-transparent dark:from-blue-900/20',
                'text' => 'text-blue-600 dark:text-blue-400',
                'hoverText' => 'group-hover:text-blue-600 dark:group-hover:text-blue-400',
            ],
            'gray' => [
                'border' => 'hover:border-gray-500 dark:hover:border-gray-400',
                'bg' => 'bg-gray-100 dark:bg-gray-900/30',
                'gradient' => 'from-gray-50 to-transparent dark:from-gray-900/20',
                'text' => 'text-gray-600 dark:text-gray-400',
                'hoverText' => 'group-hover:text-gray-600 dark:group-hover:text-gray-400',
            ],
            'emerald' => [
                'border' => 'hover:border-emerald-500 dark:hover:border-emerald-400',
                'bg' => 'bg-emerald-100 dark:bg-emerald-900/30',
                'gradient' => 'from-emerald-50 to-transparent dark:from-emerald-900/20',
                'text' => 'text-emerald-600 dark:text-emerald-400',
                'hoverText' => 'group-hover:text-emerald-600 dark:group-hover:text-emerald-400',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.landing-page', [
            'categories' => $this->categories,
            'colorClasses' => $this->colorClasses,
        ]);
    }
}
