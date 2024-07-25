import { Skeleton } from '@mui/material';

export default function SkeletonLoader() {

    return (<>
    <div class="skeleton-container">
        <div class="header-skeleton">
            <div class="hs-column hs-first-column">
            <Skeleton className = "hs-box-large" animation="wave" variant="rectangular" width={120} height={40} />
            </div>
            <div class="hs-column hs-second-column">
            <Skeleton className = "hs-box" animation="wave" variant="rectangular" width={80} height={40} />
            <Skeleton className = "hs-box" animation="wave" variant="rectangular" width={80} height={40} />
            <Skeleton className = "hs-box" animation="wave" variant="rectangular" width={80} height={40} />
            <Skeleton className = "hs-box" animation="wave" variant="rectangular" width={80} height={40} />
            <Skeleton className = "hs-box-large" animation="wave" variant="rectangular" width={150} height={40} />
            </div>
        </div>

  {/* -- two colum main box -- */}

         <div class="skeleton-main">
          <div class="ms-column">
          <Skeleton className = "hs-box" animation="wave" variant="rectangular" width={400} height={90} />
          <Skeleton className = "hs-line"  animation="wave" variant="text" sx={{ fontSize: '1rem' }} width={400} height={30}  />
          <Skeleton className = "hs-line"  animation="wave" variant="text" sx={{ fontSize: '1rem' }} width={400} height={30} />
          <Skeleton className = "hs-box-large" animation="wave" variant="rectangular" width={150} height={40} />
          </div>
          <div class="ms-column">
          <Skeleton className = "hs-box" animation="wave" variant="rectangular" width={480} height={250} />
          </div>
        </div>

  {/* -- three colum main box -- */}

    <div class="skeleton-footer">
    <div class="sf-column">
    <Skeleton  className = "hs-box-large" animation="wave" variant="rectangular" width={235} height={250} />
    </div>
    <div class="sf-column">
    <Skeleton  className = "hs-box-large" animation="wave" variant="rectangular" width={235} height={250} />
    </div>
    <div class="sf-column">
    <Skeleton  className = "hs-box-large" animation="wave" variant="rectangular" width={235} height={250} />
    </div>
  </div>
</div>
    </>);

}


export function SkeletonTemplate() {

  const items = [1,2,3,4,5,6,7,8,9];

  return (<>
  <div class="skeleton-tmpl-container">
  <div class="skeleton-tmpl-footer">
  {items.map((item, index) => (
    <div class="sf-column" key={index}>
      <Skeleton  className = "hs-box-large" animation="wave" variant="rectangular" width={295} height={340} />
        <div className='sf-line'>
          <Skeleton className = "hs-line"  animation="wave" variant="text" sx={{ fontSize: '1rem' }} width={295} height={20}  />
          <Skeleton className = "hs-line"  animation="wave" variant="text" sx={{ fontSize: '1rem' }} width={295} height={20} />
            <Skeleton className = "hs-box-large" animation="wave" variant="rectangular" width={150} height={25} />
      </div>
      </div>
        ))}
</div>
</div>
  </>);
}

export function SkeletonSingle() {

    return(  <div class="sf-column" style={{padding:'9px'}}>
    <Skeleton  className = "hs-box-large" animation="wave" variant="rectangular" width={240} height={310} />
      {/* <div className='sf-line'>
        <Skeleton className = "hs-line"  animation="wave" variant="text" sx={{ fontSize: '1rem' }} width={240} height={20}  />
        <Skeleton className = "hs-line"  animation="wave" variant="text" sx={{ fontSize: '1rem' }} width={240} height={20} />
          <Skeleton className = "hs-box-large" animation="wave" variant="rectangular" width={150} height={25} />
    </div> */}
    </div>);

}