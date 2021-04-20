<div>
    <div class="row">
        <div class="col-lg-12 card-margin">
            <div class="card search-form">
                <div class="card-body p-0">
                    <form id="search-form" wire:submit.prevent="search">
                        <div class="row">
                            <div class="col-12">
                                <div class="row no-gutters">
                                    <div class="col-lg-11 col-md-11 col-sm-11 p-0">
                                        <input type="text" placeholder="Search..." class="form-control" id="search"
                                            name="query" wire:model="query" wire:keydown.enter="search">
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-sm-12 p-0">
                                        <button class="btn btn-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-search">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div wire:loading>
        Searching
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-margin">
                <div class="card-body">
                    <div class="row search-body">
                        <div class="col-lg-12">
                            <div class="search-result">
                                <div class="result-header">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="records">
                                                Page : <b>{{ $page }}</b>
                                                <br>
                                                Sort By : <b>{{ strtoupper($sort) }}</b> -
                                                <b>{{ strtoupper($sort_by) }}</b>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="result-actions">
                                                <div class="result-sorting">
                                                    <select class="form-control border-0" id="exampleOption"
                                                        wire:model.lazy="sort">
                                                        <option value="pages" @if($sort=="pages" ) selected @endif>Pages
                                                        </option>
                                                        <option value="title" @if($sort=="title" ) selected @endif>Title
                                                        </option>
                                                        <option value="year" @if($sort=="year" ) selected @endif>Year
                                                        </option>
                                                        <option value="filesize" @if($sort=="filesize" ) selected
                                                            @endif>File Size
                                                        </option>
                                                        <option value="extension" @if($sort=="extension" ) selected
                                                            @endif>Extension
                                                        </option>
                                                        <option value="publisher" @if($sort=="publisher" ) selected
                                                            @endif>Publisher
                                                        </option>
                                                        <option value="author" @if($sort=="author" ) selected @endif>
                                                            Author
                                                        </option>
                                                        <option value="language" @if($sort=="language" ) selected
                                                            @endif>Language
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="result-sorting">
                                                    <select class="form-control border-0" id="exampleOption"
                                                        wire:model.lazy="perPage">
                                                        <option value="25" @if($perPage=="25" ) selected @endif>25
                                                        </option>
                                                        <option value="50" @if($perPage=="50" ) selected @endif>50
                                                        </option>
                                                        <option value="100" @if($perPage=="100" ) selected @endif>100
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="result-views">
                                                    <button type="button" class="btn btn-soft-base btn-icon"
                                                        wire:click="$set('sort_by' , 'ASC')">
                                                        <img src="{{ asset('images/ascendant-arrow.png') }}" alt=""
                                                            srcset="" width="30px">
                                                    </button>
                                                    <button type="button" class="btn btn-soft-base btn-icon"
                                                        wire:click="$set('sort_by' , 'DESC')">
                                                        <img src="{{ asset('images/arrow-down-sign-to-navigate.png') }}"
                                                            alt="" width="30px">
                                                    </button>
                                                </div>
                                                <div class="result-views">
                                                    <button type="button" class="btn btn-soft-base btn-icon"
                                                        wire:click="previousPage" @if($page <=1) disabled @endif>
                                                        <img src="{{ asset('images/left-arrow.png') }}" width="25px"
                                                            alt="">
                                                    </button>
                                                    <button type="button" class="btn btn-soft-base btn-icon"
                                                        wire:click="nextPage">
                                                        <img src="{{ asset('images/right-arrow.png') }}" width="25px"
                                                            alt="">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(count($results))
                                <div class="result-body">
                                    <div class="table-responsive">
                                        <table class="table widget-26">
                                            <thead>
                                                <tr>
                                                    <td>Book</td>
                                                    <td>publisher</td>
                                                    <td>pages</td>
                                                    <td>language</td>
                                                    <td>extension</td>
                                                    <td>size</td>
                                                    <td>Action</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results as $book)
                                                <tr>
                                                    <td>
                                                        <div class="widget-26-job-title">
                                                            {!! $book['html_title'] ?? "" !!}
                                                            <p class="m-0"><a href="#"
                                                                    class="employer-name">{{ $book['author'] ?? "" }}</a>
                                                                <time><span
                                                                        class="text-muted time">{{ $book['year'] ?? "" }}</span></time>
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-info">
                                                            <p class="type m-0 "><b>{{ $book['publisher'] ?? "--" }}</b>
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-salary">{{ $book['pages'] ?? "--" }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-salary">
                                                            {{ $book['language'] ?? "--" }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-salary">
                                                            {{ $book['extension'] ?? "--" }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="widget-26-job-salary">{{ $book['size'] ?? "--" }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info"><i class="fa fa-download"></i>
                                                            Show</button>
                                                        <button class="btn btn-danger"
                                                            wire:click='download("{{ $book['md5'] }}")'><i
                                                                class="fa fa-download"></i>
                                                            Download</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @else
                                @if(is_null($query) || $query == "")
                                <p class="text-center"><b>type anything and click</b></p>
                                @else
                                <p class="text-center"><b>No Results Found</b></p>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
