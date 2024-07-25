import Radio from '@mui/material/Radio';
import RadioGroup from '@mui/material/RadioGroup';
import FormControlLabel from '@mui/material/FormControlLabel';
import FormControl from '@mui/material/FormControl';
import FormLabel from '@mui/material/FormLabel';

export default function SidebarCategory(props) {


  const category = ['all','business','ecommerce','free','educations'];
  const handelCategory = (event) =>{
   props.categoryAddRemove(event.target.checked,event.target.value);
  }

    return (
        <div class="left-column">
        <div class="category-list">
        <FormControl>
      <FormLabel id="demo-radio-buttons-group-label">Template Category</FormLabel>
      <RadioGroup
        aria-labelledby="demo-radio-buttons-group-label"
        defaultValue="all"
        name="radio-buttons-group"
      >
      {category.map((cate) => 
        <FormControlLabel onChange={handelCategory}  value={cate} control={<Radio />} label={cate} />
        )}
      </RadioGroup>
    </FormControl>

      </div> 
      </div> 
    );
  }