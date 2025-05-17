@include('layout.header')
<div class="container admin-page category-page">
    <h1>Category list</h1>
    @foreach ($categories as $category)
    <div class='d-flex category-item-admin'>
        <form action="/category/admin/update" method="POST" class='d-flex'>
            @method('PUT')
            <input name='id' class='form-control' value="{{$category->id}}" type='hidden'>
            <input name='title' class='form-control'  value='{{$category->title}}' type="text" >
            <button class="btn-warning">update</button>
        </form>
        <form action="/category/admin/delete" method="POST" class='d-flex categoryDelete' id="{{$category->id}}">
            @method('DELETE')
            <input name='id' value="{{$category->id}}" type='hidden'>
            <button class="btn-danger">delete</button>
        </form>
    </div>
    @endforeach
    <div class="category-item-admin d-flex category-create">
        <form  id="categoryAdd" action="/category/admin/create" method="POST">
            <div class="modal-body">
                <div class="form-group d-flex">
                    <input name="title" type="text" class="form-control">
                    <input class="btn btn-primary" value="Create" type="submit">
                </div>
            </div>
        </form>
    </div>
</div>


<script>


    $(document).ready(function() {
        $(".category-page .categoryDelete").on("submit", function(event) {
            event.preventDefault();

            let formValues = $(this).serialize();
            let id = $(this).find('input[name=id]').val();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/category/admin/delete",
                data: formValues,
                complete: function(data) {
                    $(`.categoryDelete#${id}`).parent().remove();
                }
            });
        });
    });


</script>
