import gutenberg from '../assets/json/gutenberg.json';
import vayu from '../assets/json/vayu-x.json';
const jsonData = vayu.concat(gutenberg);

const gutenbergtmpl = ['vayu-x','th-shop-mania','blockline','blockline-pro','blur','blur-pro','gutenberg'];
const customizer = ['topstore','royal-shop','top-store-pro','big-store','openshop-pro','jotshop','open-mart','m-shop','shopline-pro','amaz-store','almaira','gogo','novelpro','oneline','portfolioline','featured'];
const elementor = ['th-shop-mania','elementor'];

const builderHandel = (builder) => {
        if (customizer.includes(builder)) {
            return 'customizer';
        } else if(elementor.includes(builder)){
            return 'elementor';
        } else if(gutenbergtmpl.includes(builder)){
            return 'gutenberg';
        }
}

const defaultJsonData = jsonData.filter(template => builderHandel(template.builder_theme) === 'gutenberg' && template.category.includes('all'));

const templateData = ( state = defaultJsonData, action) =>{

    switch(action.type){
        case "TEMPLATE_DATA" : return  jsonData.filter(template => builderHandel(template.builder_theme) === action.payload && template.category.includes(action.cate));

        default: return state;
    }

}

const templateSelect = ( state = {cate:'all',builder:'gutenberg'}, action) =>{

    switch(action.type){
        case "CATE_BUILDER" : return {cate:action.payload,builder:action.builderload};

        default: return state;
    }

}
const initialStateTF = false;

const trueFalse = ( state = initialStateTF, action) =>{
    switch(action.type){
        case "ACTION" : return action.payload;
        case "BUILDER" : return action.payload;

        default: return state;
    }

}

const tmplInstall = ( state = 'Installing Start', action) =>{
    switch(action.type){
        case "INSTALL" : return action.payload;
        default: return state;
    }

}

const stepLoad = ( state = {
    iframe:false,
    createWebsite:false,
    install:false,
    sucess:false
} , action) =>{

    switch(action.type){
        case "STEP1" : return {
    iframe:action.payload,
    createWebsite:false,
    install:false,
    success:false
}

    case "STEP2" : return {
    iframe:false,
    createWebsite:action.payload,
    install:false,
    success:false
}

case "STEP3" : return {
    iframe:false,
    createWebsite:false,
    install:action.payload,
    success:false
}

case "STEP4" : return {
    iframe:false,
    createWebsite:false,
    install:false,
    success:action.payload
}
        default: return state;
    }

}



// case "ADD" : return state.concat(action.payload);

// case "REMOVE" : return state.filter(function(initialState) { return initialState !== action.payload });

export {templateData, templateSelect, trueFalse,tmplInstall,stepLoad};

