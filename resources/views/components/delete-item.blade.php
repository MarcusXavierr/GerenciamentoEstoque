<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#item-{{$item->id}}">
    <i class="fa-solid fa-trash-can"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="item-{{$item->id}}" tabindex="-1" aria-labelledby="item-{{$item->id}}Label" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="item-{{$item->id}}Label">Tem certeza que quer deletar esse item?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        Não será possivel recuperar o item deletado
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
        <form action="{{$route}}" method='post'>
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Sim</button>
        </form>
        </div>
    </div>
    </div>
</div>