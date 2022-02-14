<template>
    <textarea :name="name" id="" ref="editor" v-model="value"></textarea>
</template>

<script>
export default {
    props: ['input', 'name'],
    data(){
        return {
            value: this.input,
            isChanging: false
        }
    },
    mounted() {
        let editor = this;
        $(this.$refs.editor).summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', []],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onChange(content, $editable) {
                    editor.value = content;
                    if (!editor.isChanging) {
                        editor.isChanging = true;
                        editor.$nextTick(function() {
                            editor.isChanging = false;
                        });
                    }
                }
            }
        });
    },
    watch: {
        value(newVal, oldVal) {
            Event.$emit('editorChanged', {
                prop: this.name,
                value: newVal,
            });
        },
        input(newVal, oldVal) {
            if (!this.isChanging) {
                $(this.$refs.editor).summernote('code', newVal ? newVal : '');
                this.isChanging = false;
            }
        }
    }
}
</script>
