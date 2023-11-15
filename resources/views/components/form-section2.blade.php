@props(['submit'])

<div {{ $attributes->merge(['class' => '']) }}>

    <br>
    <div class="panel panel-inverse"  data-sortable-id="form-plugins-1" >
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">{{ $description }}</h4>
        </div>
        <!-- begin panel-body -->
        <div class="panel-body panel-form">
            <!-- end panel-heading -->
            <form wire:submit.prevent="{{ $submit }}" class="form-horizontal form-bordered" >
                {{ $form }}
                @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                    {{ $actions }}
                </div>
                @endif
            </form>
        </div>
        <!-- end panel-body -->
    </div>
</div>
