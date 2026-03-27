<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    duration_minutes: 60,
    price_per_person: 0,
});
</script>

<template>
    <Head title="Create Workshop" />
    <AppLayout>
        <div class="p-6">
            <h1>Create Workshop</h1>
            <Button as-child variant="ghost"
                ><Link :href="route('instructor.workshops.index')"
                    >← Back</Link
                ></Button
            >

            <form
                @submit.prevent="form.post(route('instructor.workshops.store'))"
            >
                <div>
                    <Label>Name</Label>
                    <Input v-model="form.name" />
                    <p v-if="form.errors.name">{{ form.errors.name }}</p>
                </div>
                <div>
                    <Label>Description</Label>
                    <Textarea v-model="form.description" />
                    <p v-if="form.errors.description">
                        {{ form.errors.description }}
                    </p>
                </div>
                <div>
                    <Label>Duration (minutes)</Label>
                    <Input type="number" v-model="form.duration_minutes" />
                    <p v-if="form.errors.duration_minutes">
                        {{ form.errors.duration_minutes }}
                    </p>
                </div>
                <div>
                    <Label>Price per person (Ft)</Label>
                    <Input type="number" v-model="form.price_per_person" />
                    <p v-if="form.errors.price_per_person">
                        {{ form.errors.price_per_person }}
                    </p>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Create</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
