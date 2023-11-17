<script setup lang="ts">
    import { onMounted,ref } from 'vue';
    import { insertFirebase } from "../utils/firebase"
    
    onMounted(() => {
        console.log("good morning!")
        interface Person {
            name: string;
            age: number;
            email: string;
        }
        const newPerson: Person = {
            name: "John Doe",
            age: 25,
            email: "john.doe@example.com",
        };
        
        //insertFirebase(newPerson)
    })
</script>
<template></template>