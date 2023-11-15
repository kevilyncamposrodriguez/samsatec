<?php

namespace App\Http\Livewire\Dropbox;

use Livewire\Component;
use App\Models\DropboxFile;
use Spatie\Dropbox\Client;
use Illuminate\Support\Facades\Storage;

class DropboxComponent extends Component
{
    public $dropbox;
    public function mount()
    {
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
        $files = DropboxFile::orderBy('created_at', 'desc')->get();
        //  dd(Dropbox::files()->listContents($path = ''));
        // $list = Dropbox::files()->listContents($path = '');
    }
    public function render()
    {
        return view('livewire.dropbox.dropbox-component');
    }
}
