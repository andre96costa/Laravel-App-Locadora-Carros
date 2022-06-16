<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <card-component titulo="Buscar Modelos">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="idInput" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do Modelo.">
                                    <input v-model="busca.id" type="number" class="form-control" id="idInput" aria-describedby="idHelp">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component  titulo="Modelo" id="nomeModeloInput" id-help="nomeModeloInputHelp" texto-ajuda="Opcional. Informe o Modelo.">
                                    <input v-model="busca.nome" type="text" class="form-control" id="nomeModeloInput" aria-describedby="nomeModeloInputHelp">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Lista dos modelos">
                    <template v-slot:conteudo>
                        <table-component 
                            v-bind:dados="modelos.data" 
                            v-bind:visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modeloModalVisualizar'}"
                            v-bind:atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modeloModalAtualizar'}" 
                            v-bind:remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modeloModalRemover'}"
                            v-bind:titulos="{
                                id: {titulo: 'ID', tipo: 'text', show: true},
                                nome: {titulo: 'Nome', tipo: 'text', show: true},
                                imagem: {titulo: 'Imagem', tipo: 'imagen', show: true},
                                created_at: {titulo: 'Data Inserção', tipo: 'data', show: true},
                                numero_portas: {titulo: 'numero_portas', tipo: 'number', show: false},
                                air_bag: {titulo: 'air_bag', tipo: 'checkbox', show: false},
                                abs: {titulo: 'abs', tipo: 'checkbox', show: false},
                                lugares: {titulo: 'lugares', tipo: 'number', show: false},
                                marca_id: {titulo: 'marca_id', tipo: 'number', show: false},
                                marca: {titulo: 'marca', tipo: 'text[]', show: false},
                            }"
                        ></table-component>
                    </template>
                    <template v-slot:rodape>
                        <div></div>
                        <paginate-component>
                            <li v-for="(l, key) in modelos.links" :key="key" v-bind:class="l.active ? 'page-item active' : 'page-item'">
                                <a class="page-link" href="#" v-html="l.label" v-on:click="paginacao(l)" ></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary btn-sm float-right align-self-end" style="height: 28px;" data-bs-toggle="modal" data-bs-target="#modeloModal" @click="clearModalFields()">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- MODAL INCLUSAO DE MODELO -->
        <modal-component id="modeloModal" title="Adicionar Novo Modelo" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso" v-if="$store.state.transacao.status == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao cadastrar modelo" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <input-container-component titulo="Modelo do carro" id="nomeInput">
                    <input type="text" class="form-control" id="nomeInput" placeholder="Modelo" v-model="nomeModelo">
                </input-container-component>  
                <input-container-component titulo="Imagem modelo" id="imagemModeloInput" texto-ajuda="Selecione uma imagem no formato PNG">
                    <br>
                    <input type="file" class="form-control-file" id="imagemModeloInput" v-on:change="carregarImagem($event)">
                </input-container-component> 
                <div class="row">
                    <div class="col-md-6">
                        <input-container-component titulo="Quantidade de portas" id="qtdPortasInput">
                            <input type="number" class="form-control" id="qtdPortasInput" v-model="numero_portas">
                        </input-container-component>
                    </div>
                    <div class="col-md-6">
                        <input-container-component titulo="Quantidade de lugares" id="qtdLugaresInput">
                            <input type="number" class="form-control" id="qtdLugaresInput" v-model="lugares">
                        </input-container-component>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input-container-component titulo="Possui Air-bag" id="airBagInput">
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="airBagInput" id="airBagInputSim" value="1" v-model="air_bag">
                                <label class="form-check-label" for="airBagInputSim">
                                    Sim
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="airBagInput" id="airBagInputNao" value="0" v-model="air_bag">
                                <label class="form-check-label" for="airBagInputNao">
                                    Não
                                </label>
                            </div>
                        </input-container-component>
                    </div>
                    <div class="col-md-6">
                        <input-container-component titulo="Possui freio Abs" id="absInput">
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="absInput" id="absInputSim" value="1" v-model="abs">
                                <label class="form-check-label" for="absInputSim">
                                    Sim
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="absInput" id="absInputSimNao" value="0" v-model="abs">
                                <label class="form-check-label" for="absInputSimNao">
                                    Não
                                </label>
                            </div>
                        </input-container-component>
                    </div>
                </div>
                <input-container-component titulo="Marca do modelo" id="marcaSelectInput">
                    <select class="form-select" aria-label="selecao da marca" v-model="marca_id">
                        <option value="">Selecione uma marca</option>
                        <option v-for="(v) in marcas.data" :key="v.id" :value="v.id">{{ v.nome }}</option>
                    </select>
                </input-container-component> 
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
                <button type="button" class="btn btn-primary" v-on:click="salvar()" >Adicionar</button>
            </template>
        </modal-component>

        <!-- MODAL VISUALIZAR DADOS MODELO -->
        <modal-component id="modeloModalVisualizar" title="Dados do modelo" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso" v-if="$store.state.transacao.status == 'visualizar'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao cadastrar modelo" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-4">
                        <input-container titulo="Imagem modelo">
                            <img v-bind:src="'/storage/'+$store.state.item.imagem" :alt="'imagem-'+$store.state.item.nome" v-if="$store.state.item.imagem" style="width: 100%;">
                        </input-container>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-4">
                                <input-container titulo="ID">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                                </input-container>
                            </div>
                            <div class="col-8">
                                <input-container titulo="Nome">
                                        <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled>
                                </input-container>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <input-container titulo="Qtd. Portas">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.numero_portas" disabled>
                                </input-container>
                            </div>
                            <div class="col-6">
                                <input-container titulo="Qtd. Lugares">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.lugares" disabled>
                                </input-container>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <input-container titulo="Air-bags">
                                    <br>
                                    <span v-if="$store.state.item.air_bag">Sim</span>
                                    <span v-else>Não</span>
                                </input-container>
                            </div>
                            <div class="col-6">
                                <input-container titulo="Freio abs?">
                                    <br>
                                    <span v-if="$store.state.item.abs">Sim</span>
                                    <span v-else>Não</span>
                                </input-container>
                            </div>
                        </div>
                        <hr>
                        <div class="row" v-if="$store.state.item.marca">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <input-container titulo="Marca">
                                            <input type="text" class="form-control" v-bind:value="$store.state.item.marca.nome" disabled>
                                        </input-container>
                                    </div>
                                    <div class="col-6">
                                        <input-container titulo="">
                                            <img v-bind:src="'/storage/'+$store.state.item.marca.imagem" :alt="'imagem-'+$store.state.item.marca.nome" v-if="$store.state.item.marca.imagem" style="width: 100%;">
                                        </input-container>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $store.state.item}}
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearStore()">Cancelar</button>
            </template>
        </modal-component>

        <!-- MODAL DE REMOÇÃO DE MODELOS -->
        <modal-component id="modeloModalRemover" title="Remover Modelo" >
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Remoção de registro" v-if="$store.state.transacao.status == 'removido'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao remover o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'removido'">
                <span>Tem certeza que deseja remover está marca?</span>
                <input-container titulo="ID">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                </input-container>
                <input-container titulo="Nome">
                    <input type="text" class="form-control" v-bind:value="$store.state.item.nome" disabled>
                </input-container>
            </template>
            <template v-slot:rodape>
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <button type="button" class="btn btn-danger" v-on:click="remover()" v-if="$store.state.transacao.status != 'removido'">Remover</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </template>
        </modal-component>

        <!-- MODAL DE ATUALIZACAO DE MARCAS -->
        <modal-component id="modeloModalAtualizar" title="Atualizar Modelo" :data-modalIsOpen="isModalClosed = $store.state.isModalOpened">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização de registro" v-if="$store.state.transacao.status == 'atualizar'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Errou ao atualizar o retistro" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="row">
                    <div class="col-4">
                        <input-container titulo="Imagem modelo">
                            <img v-bind:src="'/storage/'+$store.state.item.imagem" :alt="'imagem-'+$store.state.item.nome" v-if="$store.state.item.imagem" style="width: 100%;">
                        </input-container>
                        <input-container-component titulo="Imagem modelo" id="imagemModeloInput" texto-ajuda="Selecione uma imagem no formato PNG">
                            <input type="file" class="form-control-file" id="imagemModeloInputEnviar" v-on:change="carregarImagem($event)" style="display: none;" >
                            <button type="button">
                                <label for="imagemModeloInputEnviar">
                                    Enviar arquivo
                                </label>
                            </button>
                            <span v-if="arquivoImagem != ''" style="font-size: 12px;">{{ arquivoImagem[0].name }}</span>
                        </input-container-component> 
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-4">
                                <input-container titulo="ID">
                                    <input type="text" class="form-control" v-bind:value="$store.state.item.id" disabled>
                                </input-container>
                            </div>
                            <div class="col-8">
                                <input-container titulo="Nome">
                                        <input type="text" class="form-control" :value="$store.state.item.nome" @change="atualizarModeloInputs($event,'nome')">
                                </input-container>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <input-container titulo="Qtd. Portas">
                                    <input type="text" class="form-control" :value="$store.state.item.numero_portas" @change="atualizarModeloInputs($event,'numero_portas')">
                                </input-container>
                            </div>
                            <div class="col-6">
                                <input-container titulo="Qtd. Lugares">
                                    <input type="text" class="form-control" :value="$store.state.item.lugares" @change="atualizarModeloInputs($event,'lugares')">
                                </input-container>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <input-container-component titulo="Possui freio Abs" id="absInput">
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absInput" @change="atualizarModeloInputs($event,'abs')" id="absInputSim" value="1" :checked="$store.state.item.abs == 1">
                                        <label class="form-check-label" for="absInputSim">
                                            Sim
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absInput" @change="atualizarModeloInputs($event,'abs')" id="absInputSimNao" value="0" :checked="$store.state.item.abs == 0">
                                        <label class="form-check-label" for="absInputSimNao">
                                            Não
                                        </label>
                                    </div>
                                </input-container-component>
                            </div>
                            <div class="col-6">
                                <input-container-component titulo="Possui Air-bags" id="airbagInput">
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="airbagInput" @change="atualizarModeloInputs($event,'air_bag')" id="airbagInputSim" value="1" :checked="$store.state.item.air_bag == 1">
                                        <label class="form-check-label" for="airbagInputSim">
                                            Sim
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="airbagInput" @change="atualizarModeloInputs($event,'air_bag')" id="airbagInputNao" value="0" :checked="$store.state.item.air_bag == 0">
                                        <label class="form-check-label" for="airbagInputNao">
                                            Não
                                        </label>
                                    </div>
                                </input-container-component>
                            </div>
                        </div>
                        <hr>
                        <div class="row" v-if="$store.state.item.marca">
                            <div class="col-12">
                                <input-container-component titulo="Marca do modelo" id="marcaSelectInput">
                                    <select class="form-select" aria-label="selecao da marca" @change="atualizarModeloInputs($event, 'marca')">
                                        <template v-for="(v) in marcas.data" >
                                            <option :key="v.id" :value="v.id" :selected="v.id == isSelected.id">{{ v.nome }}</option>
                                        </template>
                                    </select>
                                </input-container-component> 
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <button type="button" class="btn btn-primary" v-on:click="atualizar()" >Atualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearModalFields()">Cancelar</button>
                </div>
            </template>
        </modal-component>
    </div>
</template>

<script>
    import InputContainer from './InputContainer.vue';
    export default {
        components: { InputContainer },
        mounted() {
            this.carregarLista();
            this.carregarMarcas();
        },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlPaginacao: '',
                urlFiltro: '',
                nomeModelo: '',
                arquivoImagem: [],
                numero_portas: '',
                lugares: '',
                air_bag: '',
                abs: '',
                marca_id: '',
                transacaoStatus: '',
                transacoesDetalhes: {},
                modelos: {data: []},
                busca: { id: '', nome: ''},
                marcas: {data: []},
                isModalClosed: this.$store.state.isModalOpened,
                objTemp: {}
            }
        },
        methods: {
            atualizarModeloInputs(e, nameField) {
                if (nameField == 'nome') {
                    this.nomeModelo = e.target.value;
                }
                if (nameField == 'numero_portas') {
                    this.numero_portas = e.target.value;
                }
                if (nameField == 'lugares') {
                    this.lugares = e.target.value;
                }
                if (nameField == 'abs') {
                    this.abs = e.target.value;
                }
                if (nameField == 'air_bag') {
                    this.air_bag = e.target.value;
                }
                if (nameField == 'marca') {
                    this.marca_id = e.target.value;
                }
            },
            // atualizarMarcaNoSelectModelo(e){
            //     this.marca_id = e.target.value;
            // },
            carregarImagem(e) {
                console.log();
                this.arquivoImagem = e.target.files
            },
            paginacao(l) {
                if (l.url) {
                    this.urlPaginacao = l.url.split('?')[1];
                    this.carregarLista();
                }
            },
            clearStore() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                this.$store.state.transacao.dados = '';
                this.$store.state.item = {};
            },
            clearModalFields() {
                this.nomeModelo = '';
                this.arquivoImagem = [];
                imagemModeloInput.value = '';
                this.numero_portas ='';
                this.lugares = '';
                this.air_bag = '';
                this.abs = '';
                this.marca_id = '';
                imagemModeloInputEnviar.value = '';
                this.clearStore();
            },
            carregarMarcas() {
                let url = 'http://localhost:8000/api/v1/marca/lista';
                axios.get(url)
                .then(response => {
                    this.marcas.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
                axios.get(url)
                .then(response => {
                    this.modelos = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            },
            salvar() {
                this.$store.state.transacao.status = '';
                this.$store.state.transacao.mensagem = '';
                let formData = new FormData();
                formData.append('nome', this.nomeModelo);
                formData.append('imagem', this.arquivoImagem[0]);
                formData.append('numero_portas', this.numero_portas);
                formData.append('lugares', this.lugares);
                formData.append('air_bag', this.air_bag);
                formData.append('abs', this.abs);
                formData.append('marca_id', this.marca_id);
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                }
                axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.$store.state.transacao.mensagem = 'ID do registro '+ response.data.id;
                    this.$store.state.transacao.status = 'adicionado';
                    this.carregarLista();
                })
                .catch(errors => {
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                    this.$store.state.transacao.status = 'erro';
                });
            },
            remover() {
                let url = this.urlBase+'/'+this.$store.state.item.id;
                axios.delete(url)
                .then((response) => {
                    this.$store.state.transacao.mensagem = 'O registro foi removido com sucesso!';
                    this.$store.state.transacao.status = 'removido';
                    this.carregarLista();
                })
                .catch((errors, message) => {
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.status = 'erro';
                });
            },
            atualizar() {
                let url = this.urlBase+'/'+this.$store.state.item.id;
                let formData = new FormData();
                formData.append('_method', 'PATCH');
                if (this.nomeModelo) {
                    formData.append('nome', this.nomeModelo);
                } else {
                    formData.append('nome', this.$store.state.item.nome);
                }
                if (this.arquivoImagem[0]) {
                    formData.append('imagem', this.arquivoImagem[0]);
                }
                if (this.numero_portas) {
                    formData.append('numero_portas', this.numero_portas);
                }
                if (this.lugares) {
                    formData.append('lugares', this.lugares);
                }
                if (this.abs) {
                    formData.append('abs', this.abs);
                }
                if (this.air_bag) {
                    formData.append('air_bag', this.air_bag);
                }
                if (this.marca_id) {
                    formData.append('marca_id', this.marca_id);
                }
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                };
                axios.post(url, formData, config)
                .then((response) => {
                    imagemModeloInputEnviar.value = '';
                    this.arquivoImagem = [];
                    this.$store.state.transacao.mensagem = 'Registro atualizado!';
                    this.$store.state.transacao.status = 'atualizar';
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.mensagem = '';
                    this.$store.state.transacao.dados = errors.response.data.errors;
                    this.$store.state.transacao.status = 'erro';
                });
            },
            pesquisar() {
                let filtro = '';
                this.urlFiltro = '';
                for (const key in this.busca) {
                    if (this.busca[key]) {
                        if (filtro != '') {
                            filtro += ';'
                        }
                        filtro += key+':like:%'+this.busca[key]+'%';
                    }
                }
                if (filtro != '') {
                    this.urlPaginacao = 'page=1';
                    this.urlFiltro = '&filtro='+filtro;
                }
                this.carregarLista();
            },
        },
        computed: {
            isSelected() {
                if (this.$store.state.item.marca_id) {
                    return {
                        id: this.$store.state.item.marca_id,
                        nome: this.$store.state.item.marca.nome,
                    };
                } else {
                    return 0;
                }
            },
        },
        watch: {
            isModalClosed(e) {
                if (!this.isModalClosed) {
                    this.clearModalFields();
                } 
            }
        }
    }
</script>