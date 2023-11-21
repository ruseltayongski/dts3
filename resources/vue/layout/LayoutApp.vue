<script setup lang="ts">
    import { onMounted,ref } from 'vue';
    import { insertFirebase, readFirebase, testData } from "../utils/firebase"
import { Console } from 'console';
    
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
        current_user_section_id: {
            type: Number,
            default: null,
        }
    });

    declare global {
        interface Window {
            testData: (current_user_section: any) => void;
            insertFirebase: (current_user_section: any) => void;
        }
    }

    window.testData = testData;
    window.insertFirebase = insertFirebase;

    onMounted(() => {
        if (props.fb_accepted) {
            props.fb_accepted.forEach((item: AcceptedItem, index: number) => {
                insertFirebase(item)
            });
        }
        readFirebase(props.current_user_section_id);
    })
</script>
<template></template>