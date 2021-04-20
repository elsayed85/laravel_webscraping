<?php

namespace App\Http\Livewire\GenLib;

use App\Services\GenLib;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $query = "";
    public $results = [];
    public $sort = "title";
    public $sort_by = "DESC";
    public $page = 1;
    public $download_url = "";
    public $perPage = 25;

    protected $listeners = ['book_download' => "bookDownload"];


    public function render()
    {
        return view('livewire.gen-lib.search', [
            'query' => request('query')
        ]);
    }

    public function resetFilters()
    {
        $this->reset('query');
    }

    public function search()
    {
        $this->results = GenLib::search($this->query, $this->page, $this->perPage, $this->sort, $this->sort_by);
    }

    public function updatedSort($value)
    {
        $this->search();
    }

    public function updatedSortBy($value)
    {
        $this->search();
    }

    public function updatedPerPage($value)
    {
        $this->search();
    }

    public function download($md5)
    {
        $url = GenLib::download($md5)->random();
        $this->dispatchBrowserEvent('book_download', ['url' => $url]);
    }

    public function bookDownload($url)
    {
        $this->download_url = $url;
    }

    public function nextPage()
    {
        $this->page += 1;
        $this->search();
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page -= 1;
            $this->search();
        }
    }
}
