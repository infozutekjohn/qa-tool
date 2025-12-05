import React from 'react';
import '../css/app.css';
import { createRoot } from 'react-dom/client';
import Main from './Main';
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";

export default function App() {
    return <Main/>
}

const container = document.getElementById('app');

if (container) {
    const root = createRoot(container);
    root.render(<App />);
}