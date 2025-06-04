document.addEventListener("DOMContentLoaded", function () {
    console.log("Script.js carregado!");

    const modal = document.getElementById("confirmModal");
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    const closeBtn = document.querySelector('.close-delete');
    const deleteButton = document.querySelector('.btn-delete-user');

    let userIdToDelete = null;

    modal.style.display = 'none';

    // Ao clicar no botão excluir, abre o modal
    if (deleteButton) {
        deleteButton.addEventListener('click', function(e){
            e.preventDefault();
            userIdToDelete = this.getAttribute('data-id');
            modal.style.display = 'flex';
        });
    }

    // Ao clicar em confirmar exclusão
    confirmDeleteBtn.addEventListener('click', function(){
        if (userIdToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // Envia para mesma página

            // Campo oculto com ID do usuário
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = userIdToDelete;

            // Campo oculto que indica a exclusão
            const inputExcluir = document.createElement('input');
            inputExcluir.type = 'hidden';
            inputExcluir.name = 'excluir';
            inputExcluir.value = 'true';

            form.appendChild(inputId);
            form.appendChild(inputExcluir);

            document.body.appendChild(form);
            form.submit();
        }
    });

    // Cancelar exclusão fecha o modal
    cancelBtn.addEventListener('click', function(){
        modal.style.display = 'none';
    });

    // Fecha modal ao clicar no botão X
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Fecha modal ao clicar fora dele
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});
