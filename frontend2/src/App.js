import React, { useEffect } from 'react';

import './App.css';

function App() {
  useEffect(() => {
    fetch('http://localhost:8081/api/projects/1/', {
      method: 'POST',
      body: JSON.stringify({
        result: {
          limit: 10,
          offset: 0
        }
      })
    }).then((res) => res.json()).then(json => console.log(json)).catch(err => console.warn(err))
  }, [])


  return (
    <div className="App">
      <header className="App-header">
      </header>
    </div>
  );
}

export default App;


// http://localhost:8081/api/projects/find/
// http://localhost:8081/api/get/