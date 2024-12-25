using Microsoft.AspNetCore.Mvc;
using pricequotation.Models;

namespace pricequotation.Controllers
{
    public class HomeController : Controller
    {
        // Displaying initial view with default values
        [HttpGet]
        public IActionResult Index()
        {
            var model = new PriceQuotationModel
            {
                Subtotal = null, 
                DiscountPercent = null, 
                DiscountAmount = 0m, 
                Total = 0m // Set to $0.00
            };
            return View(model);
        }

        // for form submission , calculating discount and total
        [HttpPost]
        public IActionResult Index(PriceQuotationModel model) 
        {
            if (ModelState.IsValid) 
            {
                model.Calculate(); // Perform calculations if valid
            }
            else
            {
                
                model.DiscountAmount = 0m;
                model.Total = 0m;
            }

            return View(model); 
        }

        
        public IActionResult Clear()
        {
            return RedirectToAction("Index");  //resets
        }
    }
}
