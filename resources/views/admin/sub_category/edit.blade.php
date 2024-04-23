@extends('admin.layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('sub-categories.index') }}
                    " class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">

            <form action="" name="subCategoryForm" id="subCategoryForm" method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        @if (!empty($categories))
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $Subcategory->category_id ? 'selected' : ' ' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" value="{{ $Subcategory->name }}" id="name"
                                        class="form-control" placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" value="{{ $Subcategory->slug }}"
                                        class="form-control" placeholder="Slug" >
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select type="text" name="status" id="status" class="form-control"
                                        placeholder="Slug">
                                        <option value="1" {{ $Subcategory->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $Subcategory->status == 0 ? 'selected' : ' ' }}>Block
                                        </option>

                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">Show on Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="Yes" {{ $Subcategory->showHome == 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="No" {{ $Subcategory->showHome == 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection


@section('customeJS')
    <script>
        $('#name').change(function(event) {
            element = $(this);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response['status'] == true) {
                        $("#slug").val(response['slug']);
                    }
                }
            });
        });

        $('#subCategoryForm').submit(function(event) {
            event.preventDefault();
            var element = $(this);

            $.ajax({
                url: '{{ route('sub-categories.update', $Subcategory->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {

                    if (response['status'] == true) {

                        window.location.href = "{{ route('sub-categories.index') }}";

                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");

                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");


                        $("#status").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");


                        $("#category").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");

                    } else {


                        var errors = response['errors'];
                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback')
                                .html("");
                        }

                        if (errors['slug']) {
                            $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['slug']);
                        } else {
                            $("#slug").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['status']) {
                            $("#status").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['status']);
                        } else {
                            $("#status").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }

                        if (errors['category']) {
                            $("#category").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(errors['category']);
                        } else {
                            $("#category").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }
                    }

                },
                error: function(jqXHR, exception) {
                    console.log("Something went Wrong");
                }
            })
        })
    </script>
@endsection
