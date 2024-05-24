import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter as Router } from 'react-router-dom';
import Example from './components/Example';

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(
    <Router>
        <Example />
    </Router>
);
