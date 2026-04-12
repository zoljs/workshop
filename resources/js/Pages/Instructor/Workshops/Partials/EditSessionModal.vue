<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Session = {
    id: number;
    starts_at: string;
    max_capacity: number;
    update_url: string;
};

const open = ref(false);
const session = ref<Session | null>(null);
const form = ref({ starts_at: '', max_capacity: 10 });

function toDatetimeLocal(d: string) {
    const date = new Date(d);
    const pad = (n: number) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

function openModal(s: Session) {
    session.value = s;
    form.value = {
        starts_at: toDatetimeLocal(s.starts_at),
        max_capacity: s.max_capacity,
    };
    open.value = true;
}

function close() {
    open.value = false;
    session.value = null;
}

function submit() {
    if (!session.value) return;
    router.patch(
        session.value.update_url,
        {
            starts_at: form.value.starts_at,
            max_capacity: form.value.max_capacity,
        },
        {
            preserveScroll: true,
            onSuccess: () => close(),
        },
    );
}

defineExpose({ openModal });
</script>

<template>
    <Dialog :open="open" @update:open="close">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Időpont szerkesztése</DialogTitle>
            </DialogHeader>

            <div class="space-y-4">
                <div class="space-y-2">
                    <Label>Dátum és időpont</Label>
                    <Input type="datetime-local" v-model="form.starts_at" />
                </div>
                <div class="space-y-2">
                    <Label>Max létszám</Label>
                    <Input
                        type="number"
                        v-model="form.max_capacity"
                        min="1"
                        max="100"
                    />
                </div>
            </div>

            <DialogFooter class="flex flex-col gap-4 md:flex-row">
                <Button class="w-full md:w-min" variant="outline" @click="close"
                    >Mégsem</Button
                >
                <Button class="w-full md:w-min" @click="submit">Mentés</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
