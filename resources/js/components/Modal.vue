<template>
    <div class="modal fade" v-bind:id="id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="[clearStore(),isModalClosed()]"></button>
                </div>
                <div class="modal-body">
                    <slot name="alertas"></slot>
                    <slot name="conteudo"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="rodape"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['id', 'title'],
    methods: {
        clearStore() {
            this.$store.state.transacao.status = '';
            this.$store.state.transacao.mensagem = '';
            this.$store.state.transacao.dados = '';
            this.$store.state.item = {};
        },
        isModalClosed() {
            let modal = document.getElementById(this.id);
            if (modal.style.display == 'block') {
                this.$store.state.isModalOpened = false; //true
            }
        },
    },
}
</script>