export const templateData = (builder,cate) =>{
  return { 
    type: "TEMPLATE_DATA",
    payload:builder,
    cate:cate,
     }
}

export const addCateBuilder = (cate,builder) =>{
      return { 
        type: "CATE_BUILDER",
        payload:cate,
        builderload:builder
         }
}

export const addTrueFalse = (payload) =>{
  return { 
    type: "ACTION",
    payload:payload
     }
}

export const builderType = (payload) =>{
  
  return { 
    type: "BUILDER",
    payload:payload
     }
}

// installStart use
export const tmplLodaing = (payload) =>{
  return { 
    type: "INSTALL",
    payload:payload
     }
}

// Step Button Clik
export const stepOne = (payload) =>{
  return { 
    type: "STEP1",
    payload:payload
     }
}

export const stepTwo = (payload) =>{
  return { 
    type: "STEP2",
    payload:payload
     }
}

export const stepThree = (payload) =>{
  return { 
    type: "STEP3",
    payload:payload
     }
}

export const stepFour = (payload) =>{
  return { 
    type: "STEP4",
    payload:payload
     }
}