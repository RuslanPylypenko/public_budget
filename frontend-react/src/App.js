import { Header } from "./layout/Header/Header";
import { Home } from "./pages/Home/Home";

function App() {
    return (
        <>
            <Header />

            <div className="main">
                <Home />
            </div>
        </>
    );
}

export default App;
