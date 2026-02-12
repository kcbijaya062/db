using Microsoft.AspNetCore.Mvc;

namespace GuitarShop.Controllers
{
    public class HomeController : Controller
    {
        public IActionResult Index()
        {
            return View();
        }

        [Route("About")]
        public IActionResult About()
        {
            return View();
        }

        [Route("ContactUs")]
        public IActionResult ContactUs()
        {
            var contact = new Dictionary<string, string>
            {
                { "Phone", "555-123-4567" },
                { "Email", "info@myguitarshop.com" },
                { "Address", "17 E clegern Guitar St, Music City, USA" },
                { "Facebook", "facebook.com/myguitarshopusa" }
            };

            return View(contact);
        }
    }
}
