<button type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}">
    <i class="mdi mdi-file-edit label-icon"></i> {{ __('messages.edit') }}
</button>
@if($data->is_active == 1)
    <button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}">
        <i class="mdi mdi-window-close label-icon"></i> {{ __('messages.deactivate') }}
    </button>
@else
    <button type="button" class="btn btn-sm btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}">
        <i class="mdi mdi-check label-icon"></i> {{ __('messages.activate') }}
    </button>
@endif

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Update --}}
    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.edit') }} Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('dropdown.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">{{ __('messages.category') }}</label> <label class="text-danger">*</label>
                                <select class="form-control select2 category-select" name="category" data-id="{{ $data->id }}" required>
                                    <option value="" disabled selected>- {{ __('messages.select') }} -</option>
                                    @foreach($categories as $item)
                                        <option value="{{ $item->category }}" @if($data->category == $item->category) selected="selected" @endif>{{ $item->category }}</option>
                                    @endforeach
                                    <option disabled>──────────</option>
                                    <option value="NewCat">{{ __('messages.add') }} {{ __('messages.new_cat') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3 new-category-container" data-id="{{ $data->id }}" style="display: none;">
                                <label class="form-label">{{ __('messages.new_cat') }}</label> <label class="text-danger">*</label>
                                <input type="text" name="addcategory" class="form-control new-category-input" placeholder="Input {{ __('messages.new_cat') }}..">
                            </div>
                            <script>
                                $(document).ready(function () {
                                    // Handle category selection change
                                    $('.category-select').on("change", function () {
                                        const id = $(this).data("id"); // Get the unique ID
                                        const isNewCategory = $(this).val() === "NewCat";
                                        $(`.new-category-container[data-id="${id}"]`).toggle(isNewCategory);
                                        $(`.new-category-container[data-id="${id}"] .new-category-input`).prop("required", isNewCategory);
                                    });
                                    // Check initial state on page load
                                    $('.category-select').each(function () {
                                        const id = $(this).data("id");
                                        const isNewCategory = $(this).val() === "NewCat";
                                        $(`.new-category-container[data-id="${id}"]`).toggle(isNewCategory);
                                        $(`.new-category-container[data-id="${id}"] .new-category-input`).prop("required", isNewCategory);
                                    });
                                });
                            </script>  
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.name_value') }}</label> <label class="text-danger">*</label>
                                <input class="form-control" type="text" name="name_value" value="{{ $data->name_value }}" placeholder="Input {{ __('messages.name_value') }}.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">{{ __('messages.code_format') }}</label>
                                <input class="form-control" type="text" name="code_format" value="{{ $data->code_format }}" placeholder="Input {{ __('messages.code_format') }}.. ({{ __('messages.optional') }})">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-update label-icon"></i>{{ __('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Deactivate --}}
    <div class="modal fade" id="deactivate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.deactivate') }} Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('dropdown.deactivate', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.deactivate') }}</b> {{ __('messages.this_dropdown') }}?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-window-close label-icon"></i>{{ __('messages.deactivate') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Activate --}}
    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ __('messages.activate') }} Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('dropdown.activate', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            {{ __('messages.are_u_sure') }} <b>{{ __('messages.activate') }}</b> {{ __('messages.this_dropdown') }}?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                            <i class="mdi mdi-check label-icon"></i>{{ __('messages.activate') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>
