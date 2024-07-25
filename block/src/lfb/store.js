import { legacy_createStore as createStore,applyMiddleware } from 'redux';
import rootReducer from "./reducers/index";

// const stores = createReduxStore( 'my-shop', {
//     reducer: combineReducers( {
//         prices,
//         discountPercent,
//     } ),
// } );
// register( stores );


const store = createStore( rootReducer,window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__() );


export default store;
