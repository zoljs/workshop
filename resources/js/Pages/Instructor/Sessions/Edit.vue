<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    session: {
        id: number;
        starts_at: string;
        max_capacity: number;
        status: string;
        booked_count: number;
        workshop: { id: number; name: string };
    };
}>();

function toDatetimeLocal(d: string) {
    const date = new Date(d);
    const pad = (n: number) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

const form = useForm({
    starts_at: toDatetimeLocal(props.session.starts_at),
    max_capacity: props.session.max_capacity,
});
</script>

<template>
    <Head title="Edit Session" />
    <AppLayout>
        <div class="p-6">
            <h1>Edit Session — {{ session.workshop.name }}</h1>
            <p>Currently booked: {{ session.booked_count }}</p>
            <Button as-child variant="ghost"
                ><Link :href="route('instructor.workshops.index')"
                    >← Back</Link
                ></Button
            >

            <form
                @submit.prevent="
                    form.patch(route('instructor.sessions.update', session.id))
                "
            >
                <div>
                    <Label>Date & Time</Label>
                    <Input type="datetime-local" v-model="form.starts_at" />
                    <p v-if="form.errors.starts_at">
                        {{ form.errors.starts_at }}
                    </p>
                </div>
                <div>
                    <Label
                        >Max Capacity (min: {{ session.booked_count }})</Label
                    >
                    <Input
                        type="number"
                        v-model="form.max_capacity"
                        :min="session.booked_count"
                        max="100"
                    />
                    <p v-if="form.errors.max_capacity">
                        {{ form.errors.max_capacity }}
                    </p>
                </div>
                <Button type="submit" :disabled="form.processing">Save</Button>
            </form>
        </div>
    </AppLayout>
</template>
