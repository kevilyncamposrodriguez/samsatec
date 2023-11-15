<?php

namespace App\Http\Livewire\Tutorial;


use App\Models\Tutorial;
use App\Models\TutorialCategory;
use App\Models\TutorialSubcategory;
use Livewire\Component;

class TutorialsComponent extends Component
{
    public $category, $allTutorials;
    public $allCategories = [], $allSubcategories = [];
    public function mount($category = null)
    {
        $this->category =Tutorial::find($category);
    }
    public function render()
    {
        if ($this->category != null) {
            $this->allSubcategories = TutorialSubcategory::where('id_category', $this->category->id)->get();
            $this->allTutorials = Tutorial::where('tutorials.id_category', $this->category->id)
                ->leftJoin('tutorial_subcategories', 'tutorials.id_subcategory', 'tutorial_subcategories.id')
                ->select('tutorials.*', 'tutorial_subcategories.name as subcategory')->get();

            return view('livewire.tutorial.category-tutorials-component');
        } else {
            $this->allCategories = TutorialCategory::all();
            return view('livewire.tutorial.tutorials-component');
        }
    }
}
