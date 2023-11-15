<div>
    <!-- begin page-header -->
    <h1 class="mb-10">{{ $category->name }}<small></small></h1>
    <!-- end page-header -->
    <!-- begin #options -->
    <div id="options" class="m-b-5">
        <span class="gallery-option-set" id="filter" data-option-key="filter">
            <a href="#show-all" class="btn btn-default btn-xs active" data-option-value="*">Todos</a>
            @foreach($allSubcategories as $index => $subcategory)
            <a href="#{{$subcategory->id}}" class="btn btn-default btn-xs" data-option-value=".{{$subcategory->id}}">{{$subcategory->name}}</a>
            @endforeach
        </span>
    </div>
    <!-- end #options -->
    
    <!-- begin #gallery -->
    <div id="gallery" class="gallery row">
        
        @foreach($allTutorials as $index => $tutorial)
        <!-- begin image -->
        <div class="col-md-4 image {{$tutorial->id_subcategory}}">
            <div class="image-inner">
                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/tgj2FRLGmSw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <p class="image-caption">
                    #{{ $index + 1}}
                </p>
            </div>
            <div class="image-info">

                <div class="pull-right">
                    <a target="_blank" href="https://www.youtube.com/embed/tgj2FRLGmSw"> Ver en Youtube</a>
                </div>
                <div class="rating">
                    <h5 class="title">{{ $tutorial->name }}</h5>
                </div>
                <div class="desc">
                  
                    {{ $tutorial->description }}
                </div>
            </div>
        </div>
        <!-- end image -->
        @endforeach
    </div>
    <!-- end #gallery -->
</div>


<!-- ================== END PAGE LEVEL JS ================== -->