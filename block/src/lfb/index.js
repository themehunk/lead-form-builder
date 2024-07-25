import { createRoot, render, createElement } from '@wordpress/element';
import App from './App';
const el = document.getElementById( 'root' );
const root = createRoot( el );

import { Provider } from 'react-redux';
 import store from './store';

root.render(<Provider store={store}> <App /></Provider>);