<template>
    <table class="table table-hover">
        <thead>
            <tr >
                <th v-for="(t,key) in titulos" :key="key" scope="col">{{ t.titulo }}</th>
                <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>
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
                <td>
                    <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget" v-on:click="setStore(obj)">Visualizar</button>
                    <button v-if="atualizar.visivel" class="btn btn-outline-primary btn-sm" :data-bs-toggle="atualizar.dataToggle" :data-bs-target="atualizar.dataTarget" v-on:click="setStore(obj)">Atualizar</button>
                    <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm" :data-bs-toggle="remover.dataToggle" :data-bs-target="remover.dataTarget" v-on:click="setStore(obj)">Remover</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
        methods: {
            setStore(obj) {
                this.$store.state.transacao.status = '',
                this.$store.state.transacao.mensagem = '', 
                this.$store.state.item = obj;
            },
        },
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
