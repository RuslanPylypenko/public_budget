import { Header } from "./layout/Header/Header";
import { Home } from "./pages/Home/Home";
import {Footer} from "./layout/Footer/Footer";

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
