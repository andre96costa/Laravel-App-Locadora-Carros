<template>
    <table class="table table-hover">
        <thead>
            <tr >
                <th v-for="(t,key) in titulos" :key="key" scope="col">{{ t.titulo }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(obj, chave) in dadosFiltrados" v-bind:key="chave">
                <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo == 'text'">{{ valor }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'imagen'">
                        <img v-bind:src="'/storage/'+valor" :alt="'imagem-'+valor" width="40px" height="40px">
                    </span>
                    <span v-if="titulos[chaveValor].tipo == 'data'">{{ valor }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dados', 'titulos'],
        computed: {
            dadosFiltrados() {
                let campos = Object.keys(this.titulos);
                let dadosFiltrados = []
                this.dados.map((item, chave) => {
                    let itemFiltrado = {};
                    campos.forEach((campo) => {
                        itemFiltrado[campo] = item[campo];
                    });
                    dadosFiltrados.push(itemFiltrado);
                });
                return dadosFiltrados;
            },
        },
    }
</script>
