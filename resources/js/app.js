import './bootstrap';
import { createApp } from 'vue';
import QuestionForm from './components/QuestionForm.vue';

const app = createApp({});

// Register component globally
app.component('question-form', QuestionForm);

app.mount('#app');