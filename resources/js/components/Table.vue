<template>
    <table class="table table-hover">
        <thead>
            <tr >
                <template v-for="(t,key) in titulos">
                    <th v-if="t.show" :key="key" scope="col">{{ t.titulo }}</th>
                </template>
                <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(obj, chave) in dadosFiltrados" v-bind:key="chave">
                <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo == 'text' && titulos[chaveValor].show">{{ valor }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'checkbox' && titulos[chaveValor].show">{{ valor | formataBooleanGlobal }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'imagen' && titulos[chaveValor].show">
                        <img v-bind:src="'/storage/'+valor" :alt="'imagem-'+valor" width="40px" height="40px">
                    </span>
                    <span v-if="titulos[chaveValor].tipo == 'data' && titulos[chaveValor].show">{{ valor | formataDataTempoGlobal }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'text[]' && titulos[chaveValor].show">{{ valor.nome }}</span>
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
        filters: {
            formataDataTempo(d) {
                if (!d) {
                    return '';
                }
                d = d.split('T')[0];
                let data = d.split('-');
                return data[2]+'/'+data[1]+'/'+data[0];
            },
        },
        props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
        methods: {
            setStore(obj) {
                this.$store.state.isModalOpened = true;
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
