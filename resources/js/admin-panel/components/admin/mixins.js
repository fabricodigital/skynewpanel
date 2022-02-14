export default {
    created() {
        Event.$on('editorChanged', (props) => {
            this[props.prop] = props.value;
        });
    },
};
