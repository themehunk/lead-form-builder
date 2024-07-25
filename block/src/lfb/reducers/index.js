import { combineReducers } from 'redux';
import {templateData,templateSelect,trueFalse,tmplInstall,stepLoad} from "./temperature";

const rootReducer = combineReducers( { templateData,templateSelect,trueFalse,tmplInstall,stepLoad } );

export default rootReducer;