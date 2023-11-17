<script setup lang="ts">
    import { onMounted,ref } from 'vue';

    
    import Echo, {Channel} from "laravel-echo";

    const users = ref<any[]>([]);

    onMounted(() => {
        const echoWithJoin = window.Echo as Echo & { join: (channel: string) => Channel };

        echoWithJoin.join('chat')
            .here((receivedUsers: any[]) => {
                console.log(receivedUsers);
                users.value = receivedUsers;
            })
            .joining((user: any) => {
                console.log(user);
                users.value.push(user);
            }); 
    });

    /* console.log(props.sample);

        // Define an interface for a basic vehicle
        interface Vehicle {
            brand: string;
            year: number;
            start(): void;
            stop(): void;
        }

    // Define a base class that implements the Vehicle interface
        class BaseVehicle implements Vehicle {
            constructor(public brand: string, public year: number) {}

            start(): void {
                console.log(`${this.brand} is starting...`);
            }

            stop(): void {
                console.log(`${this.brand} is stopping...`);
            }
        }

    // Define a subclass Car that extends BaseVehicle
        class Car extends BaseVehicle {
            constructor(brand: string, year: number) {
                super(brand, year);
            }

            accelerate(): void {
                console.log(`${this.brand} is accelerating...`);
            }
        }

    // Define another subclass Bicycle that extends BaseVehicle
        class Bicycle extends BaseVehicle {
            constructor(brand: string, year: number) {
                super(brand, year);
            }

            ringBell(): void {
                console.log("Ring ring!");
            }
        }

    // Usage
        const myCar = new Car("Toyota", 2022);
        myCar.start();
        myCar.accelerate();
        myCar.stop();

        const myBicycle = new Bicycle("Schwinn", 2021);
        myBicycle.start();
        myBicycle.ringBell();
        myBicycle.stop();*/
    
    console.log("hahaha")
</script>
<template></template>