<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Buscar Marcas</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="idInput" class="form-label">ID</label>
                                <input type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                <div id="idHelp" class="form-text">Opcional. Informe o ID da Marca.</div>
                            </div>
                            <div class="col mb-3">
                                <label for="nomeMarcaInput" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="nomeMarcaInput" aria-describedby="nomeMarcaInputHelp">
                                <div id="nomeMarcaInputHelp" class="form-text">Opcional. Informe o Nome da Marca.</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>