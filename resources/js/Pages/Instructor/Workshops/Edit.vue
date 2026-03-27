<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    workshop: {
        id: number;
        name: string;
        description: string;
        duration_minutes: number;
        price_per_person: number;
        archived: boolean;
    };
}>();

const form = useForm({
    name: props.workshop.name,
    description: props.workshop.description,
    duration_minutes: props.workshop.duration_minutes,
    price_per_person: props.workshop.price_per_person,
});

function archive() {
    if (confirm('Archive this workshop?')) {
        router.patch(route('instructor.workshops.archive', props.workshop.id));
    }
}
</script>

<template>
    <Head title="Edit Workshop" />
    <AppLayout>
        <div class="p-6">
            <h1>Edit Workshop</h1>
            <Button as-child variant="ghost"
                ><Link :href="route('instructor.workshops.index')"
                    >← Back</Link
                ></Button
            >

            <form
                @submit.prevent="
                    form.patch(
                        route('instructor.workshops.update', workshop.id),
                    )
                "
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
                <Button type="submit" :disabled="form.processing">Save</Button>
                <Button type="button" variant="destructive" @click="archive"
                    >Archive</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
