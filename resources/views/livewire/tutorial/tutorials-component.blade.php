<div>
    <h1 class="uppercase ml-10 mt-6" style="font-size: 40px; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><strong>Sácale el mayor provecho a Samsatec </strong></h1>
    <!-- begin #gallery -->
    <div id="gallery" class="gallery" style="margin: 40px;">
        <!-- linea 1-->
            @foreach($allCategories as $index => $category)
            <!-- begin image -->
            <div class="image gallery-group-1">
                <div class="image-inner">
                    <a href="{{ route('tutorials', $category->id ) }}" >
                        <div class="img" style="background-image: url(../assets/img/tutorial/categories/{{ $category->image }})"></div>
                    </a>
                    <p class="image-caption" style="font-size: 20px;">
                        #{{ $index + 1  }}
                    </p>
                </div>
                <div class="image-info">
                    <h5 class="title">{{ $category->name }}</h5>
                    <div class="desc">
                    {{ $category->description }}
                    </div>
                </div>
            </div>
            <!-- end image -->
            @endforeach
        <!-- fin linea 1 -->
    </div>
    <!-- end #gallery -->
</div>