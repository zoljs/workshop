<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/Components/ui/alert-dialog';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const open = ref(false);

const form = useForm({
    password: '',
});

const openDialog = () => {
    open.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const closeDialog = () => {
    open.value = false;
    form.clearErrors();
    form.reset();
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeDialog(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <div class="space-y-4">
        <p class="text-sm text-muted-foreground">
            Miután a fiókod törlésre kerül, az összes adat véglegesen elvész.
            Törlés előtt mentsd le az összes fontos adatodat.
        </p>

        <AlertDialog :open="open">
            <AlertDialogTrigger as-child>
                <Button variant="destructive" @click="openDialog">
                    Fiók törlése
                </Button>
            </AlertDialogTrigger>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle
                        >Biztosan törölni szeretnéd a
                        fiókodat?</AlertDialogTitle
                    >
                    <AlertDialogDescription>
                        Ez a művelet nem visszafordítható. Az összes adatod
                        véglegesen törlésre kerül. Add meg a jelszavadat a
                        megerősítéshez.
                    </AlertDialogDescription>
                </AlertDialogHeader>

                <div class="space-y-2">
                    <Label for="password" class="sr-only">Jelszó</Label>
                    <Input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="Jelszó"
                        @keyup.enter="deleteUser"
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <AlertDialogFooter>
                    <AlertDialogCancel @click="closeDialog"
                        >Mégsem</AlertDialogCancel
                    >
                    <AlertDialogAction
                        class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Fiók törlése
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
