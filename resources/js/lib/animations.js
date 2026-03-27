// animations.ts
import { stagger } from "motion-v";

export const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: {
            delayChildren: stagger(0.25),
        },
    },
};

export const rotateVariants = {
    hidden: {
        y: 100,
        rotate: 10,
        opacity: 0,
    },
    visible: {
        y: 0,
        rotate: 0,
        opacity: 1,
        transition: {
            type: "spring",
            damping: 20,
            stiffness: 100,
        },
    },
};

export const slideVariants = {
    hidden: {
        x: -50,
        opacity: 0,
    },
    visible: {
        x: 0,
        opacity: 1,
        transition: {
            type: "spring",
            damping: 20,
            stiffness: 100,
        },
    },
};
