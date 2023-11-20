<script setup lang="ts">
    import { onMounted,ref } from 'vue';
    import { insertFirebase, readFirebase } from "../utils/firebase"
    
    interface AcceptedItem {
        route_no: String;
        section_owner: Number,
        section_accepted: Number,
        remarks: String
    }

    const props = defineProps({
        fb_accepted: {
            type: Object as () => AcceptedItem[] | null,
            default: null,
        },
        current_user_section: {
            type: Number,
            default: null,
        }
    });

    onMounted(() => {
        if (props.fb_accepted) {
            props.fb_accepted.forEach((item: AcceptedItem, index: number) => {
                insertFirebase(item)
            });
        }
        readFirebase(props.current_user_section);
    })
</script>
<template></template>