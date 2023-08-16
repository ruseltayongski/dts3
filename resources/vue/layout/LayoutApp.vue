<script setup lang="ts">
    import { onMounted, defineProps } from 'vue';
    const props = defineProps({
       "sample": String
    });
    onMounted(() => {
        console.log(props.sample);

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
        myBicycle.stop();
    })
</script>
<template>
    <h1>Hello DTS!! {{ props.sample }} wewewe</h1>
</template>