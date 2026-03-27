<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    workshops: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    workshop_id: '',
    starts_at: '',
    max_capacity: 10,
});
</script>

<template>
    <Head title="Add Session" />
    <AppLayout>
        <div class="p-6">
            <h1>Add Session</h1>
            <Button as-child variant="ghost"
                ><Link :href="route('instructor.workshops.index')"
                    >← Back</Link
                ></Button
            >

            <form
                @submit.prevent="form.post(route('instructor.sessions.store'))"
            >
                <div>
                    <Label>Workshop</Label>
                    <select v-model="form.workshop_id">
                        <option value="" disabled>Select a workshop</option>
                        <option
                            v-for="w in workshops"
                            :key="w.id"
                            :value="w.id"
                        >
                            {{ w.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.workshop_id">
                        {{ form.errors.workshop_id }}
                    </p>
                </div>
                <div>
                    <Label>Date & Time</Label>
                    <Input type="datetime-local" v-model="form.starts_at" />
                    <p v-if="form.errors.starts_at">
                        {{ form.errors.starts_at }}
                    </p>
                </div>
                <div>
                    <Label>Max Capacity</Label>
                    <Input
                        type="number"
                        v-model="form.max_capacity"
                        min="1"
                        max="100"
                    />
                    <p v-if="form.errors.max_capacity">
                        {{ form.errors.max_capacity }}
                    </p>
                </div>
                <Button type="submit" :disabled="form.processing"
                    >Add Session</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
