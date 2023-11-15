import { Header, Footer } from "./sections";
import { Home } from "./pages/Home/Home";

function App() {
    return (
        <>
            <Header />

            <div className="main">
                <Home />
            </div>

            <Footer />
        </>
    );
}

export default App;
